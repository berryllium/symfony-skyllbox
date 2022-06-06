<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Module;
use App\Entity\User;
use App\Form\Model\ArticleFormModel;
use App\Repository\ModuleRepository;
use Diplom\ArticleSubjectProviderBundle\ArticleSubjectProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

class ArticleGenerator
{
    private ArticleSubjectProvider $subjectProvider;
    private SluggerInterface $slugger;
    private EntityManagerInterface $em;
    private Environment $env;
    private ModuleRepository $moduleRepository;
    private FileUploader $fileUploader;
    private SubscriptionLevelRights $rights;


    public function __construct(
        ArticleSubjectProvider $subjectProvider,
        SluggerInterface $slugger,
        EntityManagerInterface $em,
        Environment $env,
        ModuleRepository $moduleRepository,
        FileUploader $fileUploader,
        SubscriptionLevelRights $rights
        )
    {
        $this->subjectProvider = $subjectProvider;
        $this->slugger = $slugger;
        $this->em = $em;
        $this->env = $env;
        $this->moduleRepository = $moduleRepository;
        $this->fileUploader = $fileUploader;
        $this->rights = $rights;
    }

    public function generate(ArticleFormModel $model, User $user) : Article {
        $article = new Article();
        $subject = $this->subjectProvider->getSubject($model->subject);
        $size = rand($model->sizeFrom, $model->sizeTo);
        if($this->rights->canUseSelfModule()) {
            $modulesRep = $this->moduleRepository->getModules($user->getId());
        } else {
            $modulesRep = $this->moduleRepository->getModules();
        }

        $body = '';

        for ($i = 0; $i < $size; $i++) {
            /** @var Module[] $modulesRep */
            $key = array_rand($modulesRep);
            $body .= $modulesRep[$key]->getContent();
        }

        $this->clearPlaceholders($body);
        // вставляем заголовок модуля
        $body = trim(str_replace('{{title}}', $subject->getTitles(1)[0], $body));

        // вставляем параграфы
        while (($pos = strpos($body, '{{paragraph}}')) !== false) {
            $paragraphs = $subject->getParagraphs(1);
            $body = substr($body, 0, $pos) . $paragraphs[0] . substr($body, $pos + 13);
        }
        while (($pos = strpos($body, '{{paragraphs}}')) !== false) {
            $paragraphs = $subject->getParagraphs(rand(1, 3));
            $replace = '';
            foreach ($paragraphs as $paragraph) {
                $replace .= "<p>$paragraph</p>";
            }
            $body = substr($body, 0, $pos) . $replace . substr($body, $pos + 14);
        }

        // вставляем пути к картинкам
        $images = [];
        foreach ($model->images as $image) {
            if($image instanceof UploadedFile) {
                /** @var UploadedFile $image */
                $images[] = $this->fileUploader->uploadFile($image, $image->getBasename());
            }
        }
        shuffle($images);
        preg_match_all('#imageSrc#', $body, $matches);
        $countImg = count($matches[0]);
        if($countImg && $images) {
            while (count($images) < $countImg) {
                $images[] = $images[array_rand($images)];
            }
            while (($pos = strpos($body, '{{imageSrc}}')) !== false) {
                if($pos < 3) {
                    die($body);
                } else {
                    $body = substr($body, 0, $pos) . '/uploads/' . $images[$countImg - 1] . substr($body, $pos + 12);
                    $countImg--;
                }

            }
        } else {
            $body = str_replace('{{imageSrc}}', 'https://imgholder.ru/525x300/8493a8/adb9ca&text=IMAGE+HOLDER&font=kelson', $body);
        }

        // вставляем продвигаемые слова
        if($model->words) {
            $words = [];
            foreach ($model->words as $k => $word) {
                for ($i = 0; $i < $model->wordsCount[$k]; $i++) {
                    $words[] = $word;
                }
            }
            shuffle($words);
            $textArr = explode(' ', $body);
            $keys = array_rand($textArr, count($words));
            $keys = is_array($keys) ? $keys : [$keys];
            foreach ($keys as $i => $key) {
                array_splice($textArr, $key + 1, 0, $words[$i]);
                unset($words[$i]);
                if(count($words) <= 0) {
                    break;
                }
            }
            $body = implode(' ', $textArr);
        }

        // обработку ключевых слов и остальных плейсхолдеров осуществляет twig
        $template = $this->env->createTemplate($body);
        $body = $template->render(['keyword' => $model->keyword0, 'keywords' => $model->getKeywords()]);
        $title = $model->title ?: $this->slugger->slug($subject->getName());

        $article
            ->setTitle($title)
            ->setDescription($subject->getDescription())
            ->setAuthor($user)
            ->setBody($body)
        ;

        $this->em->persist($article);
        $this->em->flush();
        return $article;
    }

    private function clearPlaceholders(&$body) {
        $body = preg_replace('#\{\{\s#', '{{', $body);
        $body = preg_replace('#\s\}\}#', '}}', $body);
    }
}
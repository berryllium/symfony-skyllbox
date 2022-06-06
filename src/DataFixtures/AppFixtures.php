<?php

namespace App\DataFixtures;

use App\Entity\Module;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $modules = [
        'two_column_text' =>
'<div class="row">
    <div class="col-sm-6">
        {{ paragraphs }}      
    </div>
    <div class="col-sm-6">
        {{ paragraphs }}
    </div>
</div>',
        'media_left_one_paragraph' =>
'<div class="media">
    <div class="media-body">
        <p>{{ paragraph }}</p>
    </div>
    <img class="ml-3" src="https://via.placeholder.com/250x250" width="250" height="250" alt="">
</div>'

    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->modules as $name => $content) {
            $module = new Module();
            $module
                ->setUser(null)
                ->setName($name)
                ->setContent($content)
            ;
            $manager->persist($module);
            $manager->flush();
        }
    }
}

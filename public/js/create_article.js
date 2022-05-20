$(function() {
    $('button#add_word_row').click(function (){
        let row = $(this).prev()
        let id = row.data('id')
        let clone = row.clone()
        clone.attr('data-id', id + 1)
        clone.find('input').each(function(){
            $(this).attr('id', $(this).attr('id').replace(id, id + 1))
            $(this).attr('name', $(this).attr('name').replace(id, id + 1))
        })
        clone.find('label').each(function(){
            $(this).attr('for', $(this).attr('for').replace(id, id + 1))
            console.log($(this).attr('for'))
            console.log($(this).attr('for').replace(id, id + 1))
        })
        $(this).before(clone)
    })
});
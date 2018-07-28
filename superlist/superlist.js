$(document).ready(function() {
    // bind sortable on mouse enter, as
    // perch's js scripts are loaded via async
    $('.superlist').mouseenter(function() {
        $(".superlist").sortable({
            handle: ".reorder-list-item",
            axis: 'y',
            containment: '.superlist',
            items: "> div:not(:last-child)"
        });
    });

    // if in a list item with existing data, pressing enter
    // will focus the next element
    $('.superlist').on('keypress', '.superlist-input', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            $this = $(this);
            $this.parents('.superlist-item-wrapper')
                 .next('.superlist-item-wrapper')
                 .find('input')
                 .focus()
                 .select();
        }
    });

    // press enter to add new item
    $('.superlist').on('keypress', '.superlist-next', function(e) {
        if (e.which == 13) {
            e.preventDefault();

            $this = $(this);

            if ($('.superlist-item').length > 0) {
                idString = $('.superlist-item').last().find('input').attr('id');
            } else {
                idString = $this.attr('id');
            }

            idSringParts = idString.split('list_');
            idPreString = idSringParts[0];
            nextIdNumber = parseInt(idSringParts[1]);

            if (isNaN(nextIdNumber)) {
                var nextIdNumber = 0;
            } else {
                nextIdNumber++;
            }

            $this.parents('.superlist-item-wrapper')
                 .clone()
                 .insertBefore($this.parents('.superlist-item-wrapper'))
                 .find('li')
                 .addClass('superlist-item')
                 .find('input')
                 .addClass('superlist-input')
                 .removeClass('superlist-next')
                 .attr({
                    id: idPreString + 'list_' + nextIdNumber,
                    name: idPreString + 'list_' + nextIdNumber
            });

            $this.val('');
        }
    });

    // remove list item by clickin the X icon
    $('.field-wrap').on('click', '.remove-list-item', function() {
        $(this).parent().remove();
    })
});
$(document).ready(function() {
    $(".superlist").sortable({
        handle: ".reorder-list-item",
        axis: 'y',
        containment: '.superlist-items',
        items: ".superlist-item-wrapper:not(:last-child)"
    });
});

Vue.component('superlist', {
    props: ['items', 'inputid', 'perchpath'],

    template: '<div class="superlist-items"><div class="superlist-item-wrapper" v-for="(item, index) in listItems"><li class="superlist-item"><span @click="remove(index)" class="remove-list-item"><img :src="removeImgpath" alt="Remove"></span><span class="reorder-list-item"><img :src="reorderImgpath" alt="Reorder"></span><input type="text" :id="theId + index" :name="theId + index" @keypress.enter.prevent="handleKeypress" v-model="listItems[index]" :placeholder="placeholder(index)" /></li></div></div>',

    data: function data() {
        return {
            theId: this.inputid + '_',
            listItems: this.items,
            removeImgpath: '//' + this.perchpath + '/addons/fieldtypes/superlist/img/delete.svg',
            reorderImgpath: '//' + this.perchpath + '/addons/fieldtypes/superlist/img/reorder.svg'
        }
    },
    computed: {
        placeholdertext : function() {
            return 'Add text'
        }
    },
    mounted: function() {
        this.listItems.push('');
    },
    methods: {
        add: function(){
            this.listItems.push('');
        },
        remove: function(index){
            itemToRemove = this.listItems.indexOf(index);
            this.listItems.splice(index, 1);
        },
        handleKeypress: function(event) {
            var key = event.which;

            if (key === 13) {
                thisId = event.target.id
                idPos = thisId.lastIndexOf('_') + 1;
                idString = thisId.substring(0, idPos);
                idNo = thisId.substring(idPos);
                nextIdNo = parseInt(idNo) + 1;
                nextIdToSelect = idString + nextIdNo;

                nextIdToFocus = document.getElementById(nextIdToSelect);

                if (nextIdToFocus == null) {
                    this.listItems.push('');
                }

                this.$nextTick(function() {
                    document.getElementById(nextIdToSelect).focus();
                });
            }
        },
        placeholder: function(index) {
            if (index === this.listItems.length - 1) {
                return 'Add another item...'
            }

            return 'Add item'
        }
    }
});

new Vue ({
    el: '#main-panel',
})
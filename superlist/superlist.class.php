<?php

class PerchFieldType_superlist extends PerchAPI_FieldType
{
    // output the form fields for the edit page
    public function render_inputs($details = array())
    {
        $id = $this->Tag->input_id();

        $class = 'superlist-input';

        $listType = ($this->Tag->ol() === 'true') ? 'ol' : 'ul';

        $inputs = "<{$listType} class='superlist'>";
        $index = 0;

        if (isset($details[$id]) && $details[$id] != '') {
            $vals = $details[$id]['content'];
        }

        $placeholderText = 'Add a list item...';

        // check if there are existing list items
        if (!empty($vals)) {
            // build list of list item inputs with existing values
            foreach ($vals as $key => $value) {
                $inputs .= '<div class="superlist-item-wrapper">';
                $inputs .= '<li class="superlist-item">';
                $inputs .= '<span class="remove-list-item"><img src="'.PERCH_LOGINPATH.'/addons/fieldtypes/superlist/img/delete.svg" alt="Remove"></span>';
                $inputs .= '<span class="reorder-list-item"><img src="'.PERCH_LOGINPATH.'/addons/fieldtypes/superlist/img/reorder.svg" alt="Reorder"></span>';
                $inputs .= $this->Form->text($id . '_' . $index, $vals[$index], $class, false, 'text', 'autocomplete="off" placeholder="Add item"');
                $inputs .= '</li>';
                $inputs .= '</div>';
                $index = ++$index;
            }

            $placeholderText = 'Add another list item...';
        }

        // add empty input to end of list for data entry
        $inputs .= '<div class="superlist-item-wrapper">';
        $inputs .= '<li>';
        $inputs .= '<span class="remove-list-item"><img src="'.PERCH_LOGINPATH.'/addons/fieldtypes/superlist/img/delete.svg" alt="Remove"></span>';
        $inputs .= '<span class="reorder-list-item"><img src="'.PERCH_LOGINPATH.'/addons/fieldtypes/superlist/img/reorder.svg" alt="Reorder"></span>';
        $inputs .= $this->Form->text($id . '_next', '', 'superlist-next superlist-input', false, 'text', 'placeholder="' . $placeholderText . '" autocomplete="off" ');
        $inputs .= '</li>';
        $inputs .= '</div>';
        $inputs .= "</{$listType}>";
        $inputs .= $this->Form->hidden($id, 'superlist');

        // return html for admin rendering
        return $inputs;
    }

    // read in the form input, prepare data for storage in the database.
    public function get_raw($post = false, $Item = false)
    {
        $id = $this->Tag->id();

        // $post should normally be set, but if it's not, try $_POST
        if ($post === false) {
            $post = $_POST;
        }

        // find the data we need from the post
        if (isset($post[$id])) {
            $listitems = [];

            foreach ($post as $key => $value) {
                // if post vars matches pattern '[id]_'
                if (strpos($key, $id . '_') === 0 && $value !== '') {
                    $listitems[] = trim($value);
                }
            }

            // store the data as we want it
            $store['content'] = $listitems;
        }

        return $store;
    }

    // take the raw data input and return process values for templating
    public function get_processed($raw = false)
    {
        if (is_array($raw) && isset($raw)) {

            $listType = ($this->Tag->ol() === 'true') ? 'ol' : 'ul';
            $classes = $this->Tag->class() ?  "class='{$this->Tag->class()}'" : '';

            $output = "<{$listType} {$classes}>";

            foreach ($raw['content'] as $listItem) {
                $output .=  '<li>' . htmlspecialchars($listItem) . '</li>';
            }

            $output .= "</{$listType}>";

            $this->processed_output_is_markup = true;
        }

        return $output;
    }

    // get the value to be used for searching
    public function get_search_text($raw = false)
    {
        if ($raw === false) {
            $raw = $this->get_raw();
        }

        if (!PerchUtil::count($raw)) {
            return false;
        }

        if (isset($raw['content'])) {
            $rawArray = $raw['content'];
            $rawString = implode(', ', $rawArray);
            return $rawString;
        }

		return false;
    }

    public function add_page_resources()
    {
        $Perch = Perch::fetch();
        $Perch->add_css(PERCH_LOGINPATH . '/addons/fieldtypes/superlist/css/superlist.css');
        $Perch->add_javascript(PERCH_LOGINPATH . '/addons/fieldtypes/superlist/js/jquery-ui.min.js');
        $Perch->add_javascript(PERCH_LOGINPATH . '/addons/fieldtypes/superlist/js/superlist.js');
    }
}
?>
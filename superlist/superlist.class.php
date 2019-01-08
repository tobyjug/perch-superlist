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
        } else {
            $vals = [];
        }
        $values = json_encode($vals);
        $perch_loginpath = $_SERVER['HTTP_HOST'] . '/' .  PERCH_LOGINPATH;

        $str = <<<EOD
<div>
    <superlist :items='$values' inputid="$id" perchpath="$perch_loginpath"></superlist>
</div>
EOD;

        $inputs .= $str;
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
        $Perch->add_javascript('https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js');
        $Perch->add_javascript(PERCH_LOGINPATH . '/addons/fieldtypes/superlist/js/superlist.js');
    }
}
?>
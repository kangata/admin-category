<?php

namespace QuetzalArc\Admin\Category;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public static function nestedOption($selectedParent = null, $parentId = null, $level = 0)
    {
        $categories = Category::select('id', 'name')
            ->where('parent_id', $parentId)
            ->orderBy('name', 'asc')
            ->get();

        $el = '';

        $index = str_repeat('**', $level);

        foreach ($categories as $category) {
            if ($category->id == $selectedParent) {
                $el .= '<option value="'.$category->id.'" selected>'.$index.' '.$category->name.'</option>';
            } else {
                $el .= '<option value="'.$category->id.'">'.$index.' '.$category->name.'</option>';
            }

            if ($category->has('children')) {
                $el .= self::nestedOption($selectedParent, $category->id, $level + 1);
            }
        }

        return $el;
    }

    public static function nestedCheckbox($parentId = null)
    {
        $categories = Category::select('id', 'name')
            ->where('parent_id', $parentId)
            ->orderBy('name', 'asc')
            ->get();

        $el = '';

        foreach ($categories as $category) {
            $el .= '<li>';
            $el .= '<label><input class="uk-checkbox" type="checkbox" name="categories[]" value="'.$category->id.'"> '.$category->name.'</label>';

            if ($category->has('children')){
                $el .= '<ul>';
                    $el .= $category->nestedCheckbox($category->id);
                $el .= '</ul>';
            }

            $el .= '</li>';
        }

        return $el;
    }

    public function children()
    {
        return $this->hasMany('QuetzalArc\Admin\Category\Category', 'parent_id', 'id');
    }
}

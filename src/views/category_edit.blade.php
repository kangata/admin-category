@extends('layouts.uikit_no_footer')

@section('navbar')
    @include('layouts.navbar_admin')
@stop

@section('content')
    <div class="uk-margin-medium-top uk-margin-large-bottom">
        <div class="uk-card uk-card-default uk-card-small uk-width-1-2@s uk-position-relative uk-position-top-center">
            <div class="uk-card-header">
                <h3 class="uk-card-title">Edit Category</h3>
            </div>
            <div class="uk-card-body">
                <form class="uk-from-stacked" action="{{ url('admin/categories/'.$category->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="uk-margin">
                    <label class="uk-form-label" for="input-name">Name</label>
                    <div class="uk-form-controls">
                        <input id="input-name" class="uk-input uk-form-small {{ ! $errors->has('name') ?: 'uk-form-danger'  }}" type="text" name="name" value="{{ $category->name }}">
                    </div>
                    {!! $errors->first('name', '<p class="uk-text-danger uk-margin-small">:message</p>') !!}
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="input-slug">Slug</label>
                    <div class="uk-form-controls">
                        <input id="input-slug" class="uk-input uk-form-small" type="text" name="slug" value="{{ $category->slug }}">
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="select-parent">Parent</label>
                    <div class="uk-form-controls">
                        <select id="select-parent" class="uk-select uk-form-small" name="parent">
                            <option value="0">None</option>
                            {!! QuetzalArc\Admin\Category\Category::nestedOption($category->parent_id) !!}
                        </select>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="input-description">Description</label>
                    <div class="uk-form-controls">
                        <textarea id="text-description" class="uk-textarea uk-form-small" name="description" rows="5"></textarea>
                    </div>
                </div>
                <div class="uk-margin uk-text-right">
                    <a class="uk-button uk-button-danger uk-button-small" href="{{ url('/admin/categories') }}">Cancel</a>
                    <button class="uk-button uk-button-primary uk-button-small" type="submit">Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@stop
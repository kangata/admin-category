@extends('layouts.uikit_no_footer')

@section('navbar')
    @include('layouts.navbar_admin')
@stop

@section('content')
    <div class="uk-margin-medium-top uk-margin-large-bottom">
        @if(session('success'))
            <div class="uk-alert-success" uk-alert>
                <span class="uk-alert-close" uk-close></span>
                <p><span uk-icon="icon: check"></span> {{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="uk-alert-danger" uk-alert>
                <span class="uk-alert-close" uk-close></span>
                <p><span uk-icon="icon: close"></span> {{ session('error') }}</p>
            </div>
        @endif

        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-1-4@m">
                <div class="uk-card uk-card-default uk-card-small">
                    <div class="uk-card-body">
                        <h4>Add New Category</h4>
                        <form class="uk-from-stacked" action="{{ url('admin/categories') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="uk-margin">
                                <label class="uk-form-label" for="input-name">Name</label>
                                <div class="uk-form-controls">
                                    <input id="input-name" class="uk-input uk-form-small {{ ! $errors->has('name') ?: 'uk-form-danger'  }}" type="text" name="name">
                                </div>
                                {!! $errors->first('name', '<p class="uk-text-danger uk-margin-small">:message</p>') !!}
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="input-slug">Slug</label>
                                <div class="uk-form-controls">
                                    <input id="input-slug" class="uk-input uk-form-small" type="text" name="slug">
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="select-parent">Parent</label>
                                <div class="uk-form-controls">
                                    <select id="select-parent" class="uk-select uk-form-small" name="parent">
                                        <option value="0">None</option>
                                        {!! QuetzalArc\Admin\Category\Category::nestedOption() !!}
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
                                <button class="uk-button uk-button-primary uk-button-small" type="submit">Save New Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- uk-width -->
            <div class="uk-width-3-4@m">
                <div class="uk-card uk-card-default uk-card-small uk-card-body uk-margin-bottom">
                    <form class="uk-search uk-search-default uk-width-1-1" action="{{ url('/admin/categories') }}" method="GET">
                        <span uk-search-icon></span>
                        <input class="uk-search-input" type="text" name="query" placeholder="Search...">
                    </form>
                </div>
                <div class="uk-card uk-card-default uk-card-small">
                    <div class="uk-card-body">
                        <div class="uk-overflow-auto">
                            <table class="uk-table uk-table-small">
                                <thead>
                                    <tr>
                                        <th class="uk-width-1-3">Name</th>
                                        <th class="uk-width-1-3">Slug</th>
                                        <th class="uk0width-1-3">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                <a class="uk-text-bold" href="">{{ $category->name }}</a>
                                                <form action="{{ url('/admin/categories/'.$category->id) }}" method="POST">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <a class="uk-button uk-button-link uk-text-capitalize" href="{{ url('/admin/categories/'.$category->id.'/edit') }}">Edit</a>
                                                    <button class="delete uk-button uk-button-link uk-text-capitalize uk-text-danger" type="button">Delete</button>
                                                </form>
                                            </td>
                                            <td>{{ $category->slug }}</td>
                                            <td>{{ $category->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($categories->total() == 0)
                            <p class="uk-text-center uk-margin-small-bottom">Data not found.</p>
                        @endif
                    </div>
                    <div class="uk-card-footer">
                        {{ $categories->links('vendor.pagination.uikit') }}
                    </div>
                </div>
            </div> <!-- uk-width -->
        </div>
    </div>
@stop

@section('scripts')
<script>
    $(document).ready(function () {
        $('.delete').on('click', function () {
            if (confirm('Data will be permanent delete. Cancel to stop, Ok to delete.')) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@stop
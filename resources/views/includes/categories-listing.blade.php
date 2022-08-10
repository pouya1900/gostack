@foreach ($categories as $category)
      <div class="col-md-3 mb-3">
        <a href="{{ url('category', $category->slug) }}" class="item-category position-relative d-block" title="{{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}">
          <img class="img-fluid rounded @if (request()->is('/')) img-category-index @endif" src="{{ $category->thumbnail ? url('public/img-category', $category->thumbnail) : asset('public/img-category/default.jpg')}}" alt="{{ $category->name }}">
          <h5 class="text-truncate px-3">{{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}</h5>
        </a>
      </div><!-- col-3-->
  @endforeach

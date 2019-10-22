@if (count($breadcrumbs))

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)
                @if ($loop->first)
                    <li><a href="{{ $breadcrumb->url }}"><i class="fas fa-fw fa-home"></i> {{ $breadcrumb->title }}</a></li>
                @else
                    <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                @endif
            @else
                <li class="active">{{ $breadcrumb->title }}</li>
            @endif

        @endforeach
    </ol>

@endif

<div class="row">
    <div class="col">
        <h3 class="page-title">{{ $title ?? 'Tiêu đề trang' }}</h3>
        <ul class="breadcrumb">
            {{-- <li class="breadcrumb-item">
                <a href="{{ route('trang-chu') }}">Tổng quan</a>
            </li> --}}
            @if (!empty($links))
                @foreach ($links as $link)
                    <li class="breadcrumb-item">
                        @if (isset($link['route']))
                            <a href="{{ route($link['route']) }}">{{ $link['label'] }}</a>
                        @else
                            {{ $link['label'] }}
                        @endif
                    </li>
                @endforeach
            @endif
            @if (!empty($active))
                <li class="breadcrumb-item active">{{ $active }}</li>
            @endif
        </ul>
    </div>
</div>

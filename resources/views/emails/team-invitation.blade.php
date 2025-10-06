@component('mail::message')
{{ __('Bạn đã được mời tham gia nhóm :team!', ['team' => $invitation->team->name]) }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __('Nếu bạn chưa có tài khoản, bạn có thể tạo một tài khoản bằng cách nhấn vào nút bên dưới. Sau khi tạo tài khoản, bạn có thể nhấn vào nút chấp nhận lời mời trong email này để chấp nhận lời mời tham gia nhóm:') }}

@component('mail::button', ['url' => route('register')])
{{ __('Tạo Tài Khoản') }}
@endcomponent

{{ __('Nếu bạn đã có tài khoản, bạn có thể chấp nhận lời mời này bằng cách nhấn vào nút bên dưới:') }}

@else
{{ __('Bạn có thể chấp nhận lời mời này bằng cách nhấn vào nút bên dưới:') }}
@endif


@component('mail::button', ['url' => $acceptUrl])
{{ __('Chấp Nhận Lời Mời') }}
@endcomponent

{{ __('Nếu bạn không mong đợi nhận được lời mời tham gia nhóm này, bạn có thể bỏ qua email này.') }}
@endcomponent

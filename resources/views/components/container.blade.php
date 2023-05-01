<div>
    <div class="container min-heigth-container bg-fibre text-white">
        <div class="pt-5 mb-5">
            @isset($title)
            <div class="text-center ">
                <h1>{{ $title }}</h1>
            </div>
            @endisset
            @isset($head) {{ $head }} @endisset
            @isset($body) {{ $body }} @endisset
        </div>
    </div>
</div>

<div>
    <div class="container min-heigth-container bg-fibre text-white">
        <div class="pt-5 mb-5">
            @isset($title)
            <div class="text-center ">
                <h1>{{ $title }}</h1>
            </div>
            @endisset
            <div class="row">
                <div class="col-12">
                    @isset($head) {{ $head }} @endisset
                </div>
                <div class="col-12">
                     @isset($body) {{ $body }} @endisset
                </div>
            </div>
        </div>
    </div>
</div>

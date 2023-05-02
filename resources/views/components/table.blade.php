<div class="container mb-5">
    @aware(['search' => "show"])

    @if($search == "show")
    <div class="row">
        <div class="col-md-3 offset-md-9">
            <input wire:model="search" type="text" class="form-control"  placeholder="Type to search...">
        </div>
    </div>
    @endif
    <div class="row">
        <table class="table table-striped mt-2">
            <thead class="table-dark">
                {{ $thead }}
            </thead>
            <tbody>
                {{ $tbody }}
            </tbody>
        </table>
    </div>
    @isset($pagination)
    <div class="row text-right">
        {{ $pagination }}
    </div>
    @endisset
</div>

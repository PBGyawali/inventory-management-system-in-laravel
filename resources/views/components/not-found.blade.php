@include('header')
<div class="row d-flex align-items-center min-vh-100">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <h1 class='text-center text-{{$colour??'primary' }}'>
                    The {{ $element??'' }} data for given date range {{ $object??'' }} could not be found.
                </h1>
            </div>
        </div>
    </div>
</div>

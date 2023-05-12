<span class="text-center position-absolute w-100"id="message" style="z-index:50">
    <?php
        $usermessages = array('error','success','info','email','inactive','message');
        $alertclasses = array('danger','success','info');

        ?>
    @foreach($usermessages as $index=>$key)
        @if(session()->has($key))
            <div class="alert alert-{{$alertclasses[$index]??$alertclasses[0] }} alert-dismissible fade show">
                <button type="button" class="close" onclick="hide()">&times;</button>
                {!! session($key) !!}
            </div>
         @endif
    @endforeach
        @if ($errors->any())
            <div>
                <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" onclick="hide()">&times;</button>
                        @foreach ($errors->all() as $error)
                            <div>Error {{$loop->iteration}}. {{ $error }}</div>
                        @endforeach
                </div>
            </div>
        @endif
</span>


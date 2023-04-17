<span class="text-center position-absolute w-100"id="message" style="z-index:50" onclick="hide()">
    <?php
        $usermessages = array('message','error'); ?>
        @foreach($usermessages as $key)
            @if(session()->has($key))  
                    <div class="alert alert-danger alert-dismissible fade show">
                        {!! session($key) !!}
                        <button type="button" class="close" onclick="hide()">&times;</button>
                    </div>
             @endif
        @endforeach
</span>

<div class="container">
    <div class="row rounded text-white bg-@if($ticket->user->id === $ticket->user_id){{"info"}}@else{{"primary"}}@endif">
        <div class="col-md-3">
            <div class="p-2">
                <div><b>{{ strtoupper($ticket->user->last_name) }} &nbsp;{{ strtoupper($ticket->user->first_name) }}</b></div>
                <span>{{ date(config('date_format'), strtotime($ticket->created_at->toDateString())) }}</span>
                <?php
                $date = new DateTime($ticket->created_at);
                $heure_comment = $date->format('H:m:i');
                ?>
                <small>{{ $heure_comment }}</small>
            </div>
        </div>
        <div class="col-md-9" style="display: flex; align-items:center;">
            <div class="p-2">{{ $ticket->message }}</div>
        </div>
    </div>
    <hr class="my-4">
    @foreach($ticket->comments as $comment)
        <div class="row rounded text-white bg-@if($ticket->user->id === $comment->user_id){{"info"}}@else{{"primary"}}@endif">
            <div class="col-md-3">
                <div class="p-2">
                    <div><b>{{ strtoupper($comment->user->last_name) }} &nbsp;{{ strtoupper($comment->user->first_name) }}</b></div>
                    <span>{{ date(config('date_format'), strtotime($comment->created_at->toDateString())) }}</span>
                    <?php
                    $date = new DateTime($comment->created_at);
                    $heure_comment = $date->format('H:m:i');
                    ?>
                    <small>{{ $heure_comment }}</small>
                </div>
            </div>
            <div class="col-md-9" style="display: flex; align-items:center;">
                <div class="p-2">{{ $comment->comment }}</div>
            </div>
        </div>
        <hr class="my-4">
    @endforeach
</div>
@extends('layout.main') @section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}
        </div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert"
                aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}
        </div>
    @endif

    <section class="no-search">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h2 class="text-center">{{ $ticket->ticket_id }}</h2>
                </div>
                <div class="row no-mrl mb-3">
                    <div class="col-md-12 mt-3">
                        <div class="form-group row">
                            <table class="table w-75 mx-auto border">
                                <tbody>
                                    <tr>
                                        <th scope="row">Catégorie</th>
                                        <td>:</td>
                                        <td>{{ $ticket->category->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status</th>
                                        <td>:</td>
                                        @if ($ticket->status == '1')
                                            <td><span class="badge badge-success">Open</span></td>
                                        @else
                                            <td><span class="badge badge-danger">{{ trans('file.Closed') }}</span></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row">Message</th>
                                        <td>:</td>
                                        <td>{{ $ticket->message }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Création</th>
                                        <td>:</td>
                                        <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card">
                <div class="row no-mrl mb-3">
                    <div class="col-md-12 mt-3">
                        <div class="form-group row">
                            @include('tickets.comments')
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $url_components = parse_url($actual_link);
            parse_str($url_components['query'], $params);
            if(!$params['close']) {                        
        ?>
            <div class="card">
                <div class="row no-mrl mb-3">
                    <div class="col-md-12 mt-3">
                        <div class="form-group row">
                            @include('tickets.reply')
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            }
        ?>
        </div>
    </section>

    <script type="text/javascript">
        $("ul#ticket").siblings('a').attr('aria-expanded', 'true');
        $("ul#ticket").addClass("show");
    </script>
@endsection

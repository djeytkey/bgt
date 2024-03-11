@extends('layout.main') @section('content')

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.General Setting')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'setting.livraisonStore', 'method' => 'post']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm" style="width: 50%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">{{trans('file.City')}}</th>
                                                <th class="text-center">{{trans('file.Montant')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lims_cities_data as $key => $city)
                                                <tr>
                                                    <td class="text-right">
                                                        {{$city->name}}
                                                        <input type="hidden" name="id[]" class="form-control" value="{{ $city->id }}" />
                                                    </td>
                                                    <td>
                                                        <input type="number" name="livraison[]" class="form-control text-center" value="{{ $city->livraison }}" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $("ul#setting").siblings('a').attr('aria-expanded','true');
    $("ul#setting").addClass("show");
    $("ul#setting #livraison-menu").addClass("active");   
</script>
@endsection
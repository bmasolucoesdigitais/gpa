@extends('adminlte::page')

@section('title', 'Abaco Tecnologia')

@section('content_header')

<h1>{{ __('general.Reports')}} - {{__('general.Outsourceds')}}</h1>

@stop

@section('content')




<div class="col-xs-12">
    <div class="box">
        <div class="box-body table-responsive ">
            
            
            
            <table id="datatable" class="table table-hover">
                <thead>
                    
                    <tr>
                        <th>id</th>
                        <th>mails</th>
                        <th>subject</th>
                        <th>message</th>
                        <th>status</th>
                        <th>created_at</th>
                        <th>updated_at</th>
                        
                        
                        
                        
                    </tr>
                </thead>
                
                <tbody>
                    
                    
                    {{--  @foreach($companies as $company)--}}
                    @foreach($mails as $mail)
                    
                    <tr>
                        <th>{{$mail->id}} <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-widget="chat-pane-toggle" data-original-title="Contacts"></th>
                        <th>{{$mail->mails}}</th>
                        <th>{{$mail->subject}}</th>
                        <th>{{$mail->message}}</th>
                        <th>{{$mail->status}}</th>
                        <th>{{$mail->created_at}}</th>
                        <th>{{$mail->updated_at}}</th>
                        
                    </tr>
                    @endforeach
                    
                    
                    
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>

@section('js')
<script type="text/javascript">
    
    $( document ).ready(function() {
        
        $('#datatable').DataTable( {
            "initComplete": function(settings, json) {
                $('div.dataTables_filter input').focus();
            }
        });
        $('[data-toggle="tooltip"]').tooltip({
            placement : 'top'
        });
        
        
        
    });
</script>


@endsection
@endsection

@extends('layouts.appFemr')

@section('content')

                    <div class="panel-heading"><center><h1>Article Approvals Needed</h1></center></div>
                    <div class="panel-body">
                        <div><a href ="/deleteArticles" class="btn btn-danger btn-sm">DELETED ARTICLES</a>
                            <hr>

                        @if( $literatures->count() > 0 )
                            <div class="table-responsive">

                                {!! Form::open([ 'method' => 'PATCH', 'action' => 'LitApprovalsController@update' ]) !!}
                                <table class="table table-striped">

                                    <thead>
                                    <tr>
                                        <th>Approve</th>
                                        <th>Delete</th>
                                        <th>Contributor Name</th>
                                        <th>Author Name</th>
                                        <th>Link</th>
                                    </tr>
                                    </thead>

                                    @foreach($literatures as $literature)

                                        <tr>

                                            <td>
                                                {!! Form::checkbox('approved[]', $literature->id, null) !!}
                                            </td>
                                            <td>
                                                {!! Form::checkbox('deletes[]', $literature->id, null) !!}
                                            </td>
                                            <td>{{$literature->contributorName}}</td>
                                            <td>{{$literature->authorName}}</td>
                                            <td>{{$literature->addLink}}</td>


                                        </tr>


                                    @endforeach

                                </table>
                                <div class="form-group">
                                    {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
                                </div>

                                {!! Form::close() !!}
                            </div>
                        @else
                            <p>There are no Articles to approve.</p>
                        @endif
                        {{--<div><h5><a href ="/deleteArticles" class="btn btn-primary btn-sm">View Deleted Articles</a></h5></div>--}}
                    </div>

@endsection
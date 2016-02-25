@permission(["documents:unmanaged::pool-{$activity->subject->pool->uid}-read", "documents:unmanaged::pool-{$activity->subject->pool->uid}-write"])
    @if($activity->subject->tag == 'file')
        <div class="event">
            <div class="label">
                <i class="file icon"></i>
            </div>
            <div class="content">
                <div class="summary">
                    {{$activity->user->present()->fullname}} uploaded <a href="{{route('backend::documents.documents.index')}}#!/{{$activity->subject->pool->uid}}/{{$activity->subject->parent_uid?:'null'}}">{{$activity->subject->title}}</a> to '{{$activity->subject->pool->title}}'
                    <div class="date">
                        {{$activity->created_at->diffForHumans()}}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpermission
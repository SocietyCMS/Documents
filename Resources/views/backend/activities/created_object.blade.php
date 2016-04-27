@permission(["documents:unmanaged::pool-{$activity->subject->pool->uid}-read", "documents:unmanaged::pool-{$activity->subject->pool->uid}-write"])
    @if($activity->subject->tag == 'file')
        <div class="event">
            <div class="label">
                <i class="file icon"></i>
            </div>
            <div class="content">
                <div class="summary">
                    {!!  trans_choice(
                    'documents::activities.created_object.summary',
                    $activity->count_in_group -1,
                    [
                        'user' => $activity->user->present()->fullname,
                        'file' => $activity->subject->title,
                        'url' => route('backend::documents.documents.index')."#!/{$activity->subject->pool->uid}/".$activity->subject->parent_uid?:'null',
                        'pool' => $activity->subject->pool->title
                    ]
                    ) !!}
                    <div class="date">
                        {{$activity->created_at->diffForHumans()}}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endpermission
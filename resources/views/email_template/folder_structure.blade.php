@extends('layouts.main')
@section('title', 'Messages')
@section('content')
<table>
    <thead>
    <tr>
        <th>Folder</th>
        <th>Unread messages</th>
    </tr>
    </thead>
    <tbody>
    <?php if($paginator->count() > 0): ?>
        <?php foreach($paginator as $folder): ?>
                <tr>
                    <td><?php echo $folder->name; ?></td>
                    <td><?php echo $folder->search()->unseen()->setFetchBody(false)->count(); ?></td>
                </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No folders found</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<?php echo $paginator->links(); ?>
@stop
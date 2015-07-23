<div class="content page-header">
	<div class="row items-push">
		<div class="col-sm-7">
			<ol class="breadcrumb">
				<li><a href="#">$CurrentProject.Title</a></li>
				<li><a href="#">$Parent.Title FIX!!</a></li>
			</ol>
			<h1 class="page-heading">$Title</h1>
		</div>
	</div>

	<% if $CurrentProject %>

	<%--<ul class="nav nav-tabs">
		<% loop $CurrentProject.Menu %>
		<li<% if $IsActive %> class="active"<% end_if %>><a href="$Link">$Title</a></li>
		<% end_loop %>
	</ul>--%>

	<ul class="nav nav-tabs">
		<% if $CurrentProject.canBackup %>
		<li><a href="$CurrentProject.Link('createsnapshot')">Create Snapshot</a></li>
		<% end_if %>
		<% if $CurrentProject.canUploadArchive %>
		<li><a href="$CurrentProject.Link('uploadsnapshot')">Upload Snapshot</a></li>
		<% end_if %>
		<li><a href="$CurrentProject.Link('snapshotslog')">Log</a></li>
	</ul>
	<% end_if %>
</div>
<div class="content">




<% with $CurrentProject %>
<% if $HasDiskQuota %>
	<% if $HasExceededDiskQuota %>
		<p class="message bad">You have exceeded the total quota of $DiskQuotaMB MB. You will need to delete old snapshots in order to create new ones.</p>
	<% else %>
		<p class="message good">You have used $UsedQuotaMB MB out of total quota $DiskQuotaMB MB quota across all environments for this project.</p>
	<% end_if %>
<% end_if %>
<% end_with %>

<% include CompleteArchiveList %>

<% include PendingArchiveList %>

<% include SnapshotImportInstructions %>

</div>

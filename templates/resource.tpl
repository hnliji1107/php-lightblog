{include file='header.tpl' title='资源下载'}
<div class="resourceInner">
	<div class="station">
		<!--上传附件start-->
		<div class="post_upload_file">
			<h3>上传附件</h3>
			<div class="successtip">
				<ul><p>请注意：<br />1.文件大小不能超过8M<br />2.文件名长度尽量不要超过200个字符</p></ul>
			</div>
			<div class="uping">
				<form action="resource.php" enctype="multipart/form-data" method="post">
					<input type="file" name="file" class="attachment_style" />
					<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />
					<input type="hidden" name="upload_attachment" value="yes" />
					<button type="submit" class="search_btn">上传</button>
				</form>
			</div>
		</div>
		<!--上传附件end-->
	</div>
	<div class="resource">
	{if $all_resource}
		<table width="100%">
			<thead>
				<tr>
					<th><div class="upload_ico">文件图标<a href="#" title="分享是一种美德，开启上传">上传▲</a></div></th>
					<th>文件名称</th>
					<th>文件大小</th>
					<th>上传时间</th>
					<th>上传者</th>
					<th>下载</th>
				</tr>
			</thead>
			<tbody>
			{section name=some loop=$all_resource}
				<tr>
					{if $all_resource[some].filetype == 'pdf' || $all_resource[some].filetype == 'js'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'txt'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'bmp'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'chm'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'css'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'doc' || $all_resource[some].filetype == 'docx'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'gif'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'html'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'jpg' || $all_resource[some].filetype == 'jpeg'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'png'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'pptx' || $all_resource[some].filetype == 'ppt'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'psd' || $all_resource[some].filetype == 'tpl'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'zip' || $all_resource[some].filetype == 'rar'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'xlsx'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else if $all_resource[some].filetype == 'xml'}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{else}
					<td width="12.80178173719376%">
						<img data-lazyload-src="images/file_blogo/{$all_resource[some].filetype}_logo.png" src="images/lazyload.png" alt="{$all_resource[some].filetype}" />
					</td>
					{/if}
					<td width="20.73719376391982%">
						<a href="loading.php?attachment_flag={$all_resource[some].attachment_flag}&attachment_id={$all_resource[some].attachment_id}&loadfile_path={$all_resource[some].filepath}" title="下载{$all_resource[some].filename}">{$all_resource[some].filename}</a>
					</td>
					<td width="14.80178173719376%">{$all_resource[some].filesize}</td>
					<td width="21.93763919821826%">{$all_resource[some].attachment_time}</td>
					<td width="12.02672605790646%">{$all_resource[some].user_name}</td>
					<td>
						<a href="loading.php?attachment_flag={$all_resource[some].attachment_flag}&attachment_id={$all_resource[some].attachment_id}&loadfile_path={$all_resource[some].filepath}" title="下载{$all_resource[some].filename}">下载</a>({$all_resource[some].downloads})
					</td>
				</tr>
			{/section}
			</tbody>
		</table>
	{else}
		<p class="empty-content">抱歉，暂无资源哦!</p>
	{/if}
	</div>
</div>
{include file='footer.tpl'}
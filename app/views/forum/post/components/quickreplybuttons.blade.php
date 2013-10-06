					<style type="text/css">
						.btn-group i {
							font-size: 14px;
						}
					</style>
					<div class="control-group">
						<div class="controls text-center">
							<div class="btn-group">
								<a href="javascript: void()" onClick="showPreview();" class="btn btn-small btn-primary" title="Preview" id="previewBtn"><i class="icon-share-alt"></i></a>
							</div>
							<div class="btn-group">
								<a href="javascript: void()" onClick="addStyle('italic');" class="btn btn-small btn-primary" title="Italic"><i class="icon-italic"></i></a>
								<a href="javascript: void()" onClick="addStyle('bold');" class="btn btn-small btn-primary" title="Bold"><i class="icon-bold"></i></a>
								<a href="javascript: void()" onClick="addStyle('underline');" class="btn btn-small btn-primary" title="Underline"><i class="icon-underline"></i></a>
								<a href="javascript: void()" onClick="addStyle('strike');" class="btn btn-small btn-primary" title="Strikethrough"><i class="icon-strikethrough"></i></a>
								<a href="javascript: void()" onClick="addStyle('code');" class="btn btn-small btn-primary" title="Code"><i class="icon-reorder"></i></a>
								<a href="javascript: void()" onClick="addStyle('center');" class="btn btn-small btn-primary" title="Align-Center"><i class="icon-align-center"></i></a>
								<a href="javascript: void()" onClick="addStyle('paragraph');" class="btn btn-small btn-primary" title="Paragraph"><i class="icon-indent-left"></i></a>
								<a href="javascript: void()" onClick="addStyle('size');" class="btn btn-small btn-primary" title="Font Size"><i class="icon-text-width"></i></a>
								<a href="javascript: void()" onClick="addStyle('color');" class="btn btn-small btn-primary" title="Font Color"><i class="icon-font"></i></a>
								<a href="javascript: void()" onClick="addStyle('url');" class="btn btn-small btn-primary" title="URL"><i class="icon-link"></i></a>
								<a href="javascript: void()" onClick="addStyle('list');" class="btn btn-small btn-primary" title="List"><i class="icon-list"></i></a>
								<a href="javascript: void()" onClick="addStyle('image');" class="btn btn-small btn-primary" title="Image"><i class="icon-picture"></i></a>
								<a href="javascript: void()" onClick="addStyle('youtube');" class="btn btn-small btn-primary" title="YouTube"><i class="icon-film"></i></a>
							</div>
							<div class="btn-group">
								<a href="javascript: void()" class="btn btn-small btn-primary dropdown-toggle" title="Icons" data-toggle="dropdown">
									<i class="icon-tag"></i> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu text-left">
									<li><a href="javascript: void()" onClick="addStyle('icon', 'heart');"><i class="icon-heart"></i> Heart</a></li>
									<li><a href="javascript: void()" onClick="addStyle('icon', 'star');"><i class="icon-star"></i> Star</a></li>
									<li><a href="javascript: void()" onClick="addStyle('icon', 'music');"><i class="icon-music"></i> Music</a></li>
									<li><a href="javascript: void()" onClick="addStyle('icon', 'comment');"><i class="icon-comment"></i> Comment</a></li>
									<li><a href="javascript: void()" onClick="addStyle('icon', 'comments');"><i class="icon-comments"></i> Comments</a></li>
									<li><a href="javascript: void()" onClick="addStyle('icon', 'quote-left');"><i class="icon-quote-left"></i> Left Quote</a></li>
									<li><a href="javascript: void()" onClick="addStyle('icon', 'quote-right');"><i class="icon-quote-right"></i> Right Quote</a></li>
									<li><a href="javascript: void()" onClick="addStyle('icon', 'lightbulb');"><i class="icon-lightbulb"></i> Lightbulb</a></li>
									<li><a href="javascript: void()" onClick="addStyle('dice');">{{ HTML::image('img/dice.png', null, array('style' => 'width: 14px;')) }} Dice</a></li>
								</ul>
							</div>
							<br /><br />
							<div class="text-left well" id="contentPreview" style="display: none; margin: 0px auto;"></div>
							{{ Form::textarea('content', (Input::old('content') != null ? Input::old('content') : $content), array('placeholder' => 'Body', 'class' => 'span10', 'id' => 'contentField', 'tabindex' => 2)) }}
						</div>
					</div>
					<script type="text/javascript">
						function addStyle(type, icon) {
							// This should be type: [openTag, closeTag].  It should match what is searched in Utility_Response_BBCode
							var tags = {
								'italic': ['[i]', '[/i]'],
								'bold': ['[b]', '[/b]'],
								'code': ['[code]', '[/code]'],
								'size': ['[size=100]', '[/size]'],
								'color': ['[color=#ffffff]', '[/color]'],
								'strike': ['[s]', '[/s]'],
								'underline': ['[u]', '[/u]'],
								'center': ['[center]', '[/center]'],
								'paragraph': ['[paragraph]', '[/paragraph]'],
								'url': ['[url=]', '[/url]'],
								'image': ['[img]', '[/img]'],
								'list': ['[list]', '[/list]'],
								'youtube': ['[youtube]', '[/youtube]'],
								// In drop down
								'icon': ['[icon='+ icon +']', ''],
								'dice': ['[dice]', ''],
							};

							var openTag = tags[type][0];
							var closeTag = tags[type][1];

							wrapText('contentField', openTag, closeTag);
						}
						function wrapText(elementID, openTag, closeTag) {
							var textArea = $('#' + elementID);
							var len = textArea.val().length;
							var start = textArea[0].selectionStart;
							var end = textArea[0].selectionEnd;
							var selectedText = textArea.val().substring(start, end);
							var replacement = openTag + selectedText + closeTag;
							textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
						}
						function showPreview() {
							if ($('#contentPreview').css('display') == 'none') {
								$.post("/forum/preview", { update: $('#contentField').val() }).done(function(data) {
									$('#contentPreview').height('auto').width($('#contentField').width());
									$('#contentField').hide();
									$('#contentPreview').empty().append(data).show();
									$('#previewBtn i').removeClass('icon-share-alt').addClass('icon-reply');
									$('#previewBtn').attr('title', 'Edit');
								});
							} else {
								$('#contentField').show();
								$('#contentPreview').hide();
								$('#previewBtn i').addClass('icon-share-alt').removeClass('icon-reply');
								$('#previewBtn').attr('title', 'Preview');
							}
						}
					</script>
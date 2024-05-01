			<div class="color-thief-analyzer panel">
				<div class="title">Color Thief Analyzer</div>

				<div class="skin-tone color">
					<div class="label">Monk Skin Tone</div>
					<div class="value" style="background-color:<%= skin_color %>" data-toggle="tooltip" title="Color: <%= skin_tone %>"></div>
				</div>

				<div class="dominant color">
					<div class="label">Dominant Color</div>
					<div class="value" style="background-color:<%= dominant_color %>" data-toggle="tooltip" title="Color: <%= dominant_color %>"></div>
				</div>

				<div class="cropped">
					<a href="<%= cropped %>" target="_blank" class="lightbox" title="<%= name %>">
						<img src="<%= cropped %>" />
					</a>
				</div>
			</div>
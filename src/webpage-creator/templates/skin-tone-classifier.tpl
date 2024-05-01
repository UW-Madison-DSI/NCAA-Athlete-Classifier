			<div class="skin-tone-classifier panel">
				<div class="title">Skin Tone Classifier</div>

				<div class="skin-tone color">
					<div class="label">Monk Skin Tone</div>
					<div class="value" style="background-color:<%= skin_color %>" data-toggle="tooltip" title="Color: <%= skin_tone %>"></div>
				</div>

				<div class="dominant color">
					<div class="label">Dominant Color1 (<%= dominant_strength1 %>%)</div>
					<div class="value" style="background-color:<%= dominant_color1 %>" data-toggle="tooltip" title="Color: <%= dominant_color1 %>"></div>
				</div>

				<div class="dominant color">
					<div class="label">Dominant Color2 (<%= dominant_strength2 %>%)</div>
					<div class="value" style="background-color:<%= dominant_color2 %>" data-toggle="tooltip" title="Color: <%= dominant_color2 %>"></div>
				</div>

				<div class="report">
					<a href="<%= report %>" target="_blank" class="lightbox" title="<%= name %>">
						<img src="<%= report %>" />
					</a>
				</div>
			</div>
function Cell (objHtml, row, col) {
	this.objectHtml = objHtml;
	this.size = 30; // `width` and `height` of cell
	this.row = row;
	this.col = col;
	this.status = true; // `true`: can mark, `false`: marked
	this.type = ''; // `x` or `o`

	this.draw();
}
Cell.prototype = {
	draw: function () {
		this.objectHtml.append('<div class="tiktok_item" col="'+this.col+'" row="'+this.row+'"></div>');
	}
}
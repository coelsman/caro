function Generator (objHtml) {
	this.objectHtml = objHtml;
	this.size = 30; // number of `cells` (instance of Cell object) in each row, each column

	this.objectHtml.addClass('tiktok');
}
Generator.prototype = {

	create: function () {
		var cells = new Array(this.size);
		this.objectHtml.append('<div class="wrap_tiktok"></div');

		// loop rows
		for (var i=0; i<this.size; i++) {
			cells[i] = new Array(this.size);

			// loop column
			for (var j=0; j<this.size; j++) {
				cells[i][j] = new Cell(this.objectHtml.find('.wrap_tiktok'), i, j);
			}

			this.objectHtml.find('.wrap_tiktok').append('<div class="clearfix"></div>');
		}

		return cells;
	}

}
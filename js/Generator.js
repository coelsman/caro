function Generator (objHtml) {
	this.objectHtml = objHtml;
	this.size = 30; // number of `cells` (instance of Cell object) in each row, each column
}
Generator.prototype = {

	create: function () {
		var cells = new Array(this.size);

		// loop rows
		for (var i=0; i<this.size; i++) {
			cells[i] = new Array(this.size);

			// loop column
			for (var j=0; j<this.size; j++) {
				cells[i][j] = new Cell(this.objectHtml, i, j);
			}
		}

		return cells;
	}

}
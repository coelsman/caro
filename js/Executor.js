function Executor () {

}
Executor.prototype = {
	checkIsHaveWinning: function (cells, nRow, nCol) {
		var	type = cells[nRow][nCol].type;
		return (this.getLongestBy_TopLeft_BottomRight(cells, nRow, nCol, type)
			|| this.getLongestBy_BottomLeft_TopRight(cells, nRow, nCol, type)
			|| this.getLongestBy_Top_Bottom(cells, nRow, nCol, type)
			|| this.getLongestBy_Left_Right(cells, nRow, nCol, type));
	},

	getLongestBy_TopLeft_BottomRight: function (cells, nRow, nCol, type) {
		var i = nRow, j = nCol, ln = 0;

		while (i >= 0 && j < 30 && cells[i][j].type == type) {
			i--; j--;
		}

		i++; j++;

		while (i >= 0 && j < 30 && cells[i][j].type == type) {
			ln++;
			i++; j++;
		}
console.log('TopLeft-BottomRight: '+ln);
		return (ln == 5) ? true : false;
	},

	getLongestBy_BottomLeft_TopRight: function (cells, nRow, nCol, type) {
		var i = nRow, j = nCol, ln = 0;

		while (i >= 0 && j < 30 && cells[i][j].type == type) {
			i++; j--;
		}

		i--; j++;

		while (i >= 0 && j < 30 && cells[i][j].type == type) {
			ln++;
			i--; j++;
		}
console.log('BottomLeft-TopRight: '+ln);
		return (ln == 5) ? true : false;
	},

	getLongestBy_Top_Bottom: function (cells, nRow, nCol, type) {
		var i = nRow, j = nCol, ln = 0;

		while (i >= 0 && j < 30 && cells[i][j].type == type) {
			i--;
		}

		i++;

		while (i >= 0 && j < 30 && cells[i][j].type == type) {
			ln++;
			i++;
		}
console.log('Top-Bottom: '+ln);
		return (ln == 5) ? true : false;
	},

	getLongestBy_Left_Right: function (cells, nRow, nCol, type) {
		var i = nRow, j = nCol, ln = 0;

		while (i >= 0 && j < 30 && cells[i][j].type == type) {
			j--;
		}

		j++;

		while (i >= 0 && j < 30 && cells[i][j].type == type) {
			ln++;
			j++;
		}
console.log('Left-Right: '+ln);
		return (ln == 5) ? true : false;
	},
}
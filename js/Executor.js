function Executor () {

}
Executor.prototype = {
	checkIsHaveWinning: function (cells, nRow, nCol) {
		var	type = cells[nRow][nCol].type;
		console.log('@@@@@@@@@@@@@@@@@@@@@@@@@@@');
		console.log(this.getLongestBy_TopLeft_BottomRight(cells, nRow, nCol, type));
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

		return ln;
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

		return ln;
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

		return ln;
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

		return ln;
	},
}
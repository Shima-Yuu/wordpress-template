const fs = require('fs');

// ディレクトリがなければ作成
function createFolders(...folderPaths) {
  folderPaths.forEach((folderPath) => {
    if (!fs.existsSync(folderPath)) {
      fs.mkdirSync(folderPath);
    }
  });
}

exports.createFolders = createFolders;

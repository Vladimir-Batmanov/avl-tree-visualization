function insert() {
    const value = document.getElementById('value').value;
    fetch('tree.php?action=insert&value=' + value)
        .then(response => response.text())
        .then(data => {
            document.getElementById('treeStructure').innerText = data;
        });
}

function search() {
    const value = document.getElementById('value').value;
    fetch('tree.php?action=search&value=' + value)
        .then(response => response.text())
        .then(data => {
            document.getElementById('searchResult').innerText = data;
        });
}

function clearTree() {
    fetch('tree.php?action=clear')
        .then(response => response.text())
        .then(data => {
            document.getElementById('treeStructure').innerText = data;
            document.getElementById('searchResult').innerText = '';
        });
}

window.onload = function() {
    fetch('tree.php?action=print')
        .then(response => response.text())
        .then(data => {
            document.getElementById('treeStructure').innerText = data;
        });
};


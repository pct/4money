if (!Number.toFixed) {
    Number.prototype.toFixed=function(n){
        return Math.round(this*Math.pow(10, n)) / Math.pow(10, n);
    }
}


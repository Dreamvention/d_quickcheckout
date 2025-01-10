// Call this function.
// The others are helpers for this one.
function getDiff(a, b){
    var diff = (isArray(a) ? [] : {});
    recursiveDiff(a, b, diff);
    return diff;
}

function recursiveDiff(a, b, node){
    var checked = [];

    for(var prop in a){
        if(typeof b[prop] == 'undefined'){
            addNode(prop, '', node);
        }
        else if(JSON.stringify(a[prop]) != JSON.stringify(b[prop])){
            // if value
            if(typeof b[prop] != 'object' || b[prop] == null){
                addNode(prop, b[prop], node);
            }
            else {
                // if array
                if(isArray(b[prop])){
                   addNode(prop, [], node);
                   recursiveDiff(a[prop], b[prop], node[prop]);
                }
                // if object
                else {
                    addNode(prop, {}, node);
                    recursiveDiff(a[prop], b[prop], node[prop]);
                }
            }
        }
    }

    for(var prop in b){
        if(typeof a[prop] == 'undefined'){
            addNode(prop, b[prop], node);
        }
    }
}

function addNode(prop, value, parent){
        parent[prop] = value;
}

function isArray(obj){
    return (Object.prototype.toString.call(obj) === '[object Array]');
}
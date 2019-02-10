[1,2].concat([3,4]) // [1,2,3,4]
"1-2-3".split("-", 2) // [1,2]




// Lodash
var result = _.chain(data)
    .map('elements')               // pluck all elements from data
    .flatten()                     // flatten the elements into a single array
    .filter({prop: 'foo'})         // exatract elements with a prop of 'foo'
    .sumBy('val')                  // sum all the val properties
    .value()

_(this.slugs)
    .map(item => _.toArray(item))
    .flatten().flatten()
    .map('car_attributes')
    .map(item => _.find(item, ['attributetype','BodyType']) )
    .map('displayvalue')
    .uniq()
    .compact()
    .value()    


// RegExp
import qs from 'qs';

class Errors{

    constructor()
    {
        this.errors = {};
    }

    record(errors){
        this.errors = errors;
    }

    has(field){
        if(this.errors[field]){
            return this.errors[field][0];
        }
    }

    any(){
        return Object.keys(this.errors).length > 0;
    }

    clear(field){
        if (field){
            delete this.errors[field];
            return;
        }

        this.errors = {};
    }

}

export default class Form{

    constructor(data)
    {
        this.originalData = data;

        this._csrf = yii.getCsrfToken();

        for(let field in data){
            this[field] = data[field];
        }

        this.errors = new Errors();

    }

    reset(){
        for(let field in this.originalData){
            this[field] = '';
        }
    }

    data(){
        let data = Object.assign({}, this);
        delete data.errors;
        delete data.originalData;
        return data;
    }


    submit(requestType, url)
    {

        return new Promise((resolve, reject) => {

            ajax[requestType](url, qs.stringify(this.data()))
                .then(response => {
                    this.onSuccess(response);
                    resolve(response.data);
                })
                .catch(error => {
                    this.onFail(error)
                    reject(error.response.data);
                });
        })

    }

    onSuccess(response) {

    }

    onFail(error){
        this.errors.record(error.response.data.data.validate);
    }

}

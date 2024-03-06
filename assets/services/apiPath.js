function apiPath(path) {
    if(process.env.NODE_ENV === 'development'){
      return 'http://localhost:8000/api/' + path;
    } else {
      return 'https://trt-conseil-195764a4327f.herokuapp.com/api/' + path;
    }
    
    }
  
  export default apiPath;
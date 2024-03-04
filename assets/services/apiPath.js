function apiPath(path) {
    if(process.env.NODE_ENV === 'development')
    console.log("http://localhost:8000/api/" + path);
    return 'http://localhost:8000/api/' + path;
    }
  
  export default apiPath;
function apiPath(path) {
    if(process.env.NODE_ENV === 'development')
    return 'http://localhost:8000/api/' + path;
    }
  
  export default apiPath;
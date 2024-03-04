import React from "react";
import { Link } from "react-router-dom";

const Button = (props) => {
  return (
    <button
      id={props.id ? props.id : ""}
      className="ctaButton"
      onClick={props.onClick}
    >
      <Link to={props.path}>{props.title}</Link>
    </button>
  );
};

export default Button;

import React from "react";

export const FieldGroup = (props) => {
  return (
    <div className="fieldGroup">
      <label htmlFor={props.id}>{props.label}</label>
      <input
        type={props.type}
        placeholder={props.placeholder}
        name={props.id}
        className="form-control"
        id={props.id}
        onChange={props.onChange}
        defaultValue={props.value || ""}
      />

      {props.error && <p className="errorMessage">{props.error}</p>}
    </div>
  );
};

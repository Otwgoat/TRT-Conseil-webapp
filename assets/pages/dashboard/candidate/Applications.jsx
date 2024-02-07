import React, { useEffect, useState } from "react";

export const Applications = () => {
  const [applications, setApplications] = useState([]);
  useEffect(() => {
    const fetchData = async () => {
      const data = await applicationApi.getAllApplications();
      setApplications(data);
      console.log(data);
    };
    fetchData();
  }, []);
  return (
    <div className="dashboardContent">
      <h2>Mes candidatures</h2>
      {applications.length <= 0 && (
        <p>Vous n'avez pas encore postulé à une annonce.</p>
      )}
      {applications &&
        applications.map((application) => <h3>{application.jobID.id}</h3>)}
    </div>
  );
};

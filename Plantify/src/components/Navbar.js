import React, { Component } from "react";
import { Link, BrowserRouter as router } from "react-router-dom";
import { useState } from "react";
import { useHistory } from "react-router-dom/cjs/react-router-dom.min";
// import { Callbacks } from "jquery";

const Navbar = () => {
  return (
    <div className="col-md-12 py-2" style={{ backgroundColor: "#0d3b04" }}>
      <nav className="navbar navbar-dark">
        <Link className="navbar-brand" to="/">
          <img
            src="assets/img.jpeg"
            style={{ height: 60, width: 60, borderRadius: 59, marginRight: 10 }}
          />
          <text style={{ fontWeight: 600, fontSize: 30 }}>
            Plant Identifier
          </text>
        </Link>

        <ul className="nav ml-auto">
          <li className="nav-item"></li>
          <li className="nav-item">
            <Link to="/" className="nav-link text-light">
              Home
            </Link>
          </li>

          <li className="nav-item">
            <Link to="/tutorials" className="nav-link text-light">
              Tutorials
            </Link>
          </li>
          <li className="nav-item">
            <Link to="/about" className="nav-link text-light">
              About
            </Link>
          </li>
          <li className="nav-item">
            <Link to="/contact" className="nav-link text-light">
              Contact
            </Link>
          </li>
          <li className="nav-item">
            <Link to="/data" className="nav-link text-light"></Link>
          </li>
        </ul>
      </nav>
    </div>
  );
};

export default Navbar;

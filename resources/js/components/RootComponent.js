import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class RootComponent extends Component {


	render() {
		return (
	        <div className="container">
	            <div className="row justify-content-center">
	                <div className="col-md-8">
	                    <div className="card">
	                        <div className="card-header">Example Component</div>
	                        <ChangeConnection device={ this.props.device } connection={ this.props.connection } />
	                        <div className="card-body">{ this.props.device }</div>
	                    </div>
	                </div>
	            </div>
	        </div>
    	);
	}
}

export default RootComponent;
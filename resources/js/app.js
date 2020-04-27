import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class ChangeConnection extends Component {

	render() {
		if ( (this.props.device == 'android') && (!this.props.connection) ) {
			return (<div className="alert alert-danger">Please switch to cellular connection</div>);
		} else if ( (this.props.device == 'ios') && (!this.props.connection) ) {
			return (<div className="btn btn-danger">Please switch to cellular connection ></div>);
		}
	}

}

export default class App extends Component {

	constructor(props) {
		super(props);
	}

	componentDidMount() {
		console.log('mounted', this.props);
		
		if (navigator.connection) {
			navigator.connection.onchange = this.onChangeConnection.bind(this);
		}
	}

	onChangeConnection() {
		// call uid => /api/connectionchanged
	}

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

if (document.getElementById('app')) {
	var elem = document.getElementById('app');
	var uid = elem.getAttribute('data-uid');
	var device = elem.getAttribute('data-device');
	var connection = elem.getAttribute('data-connection');

    ReactDOM.render(<App uid={ uid } device={ device } connection={ connection } />, elem);
}

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

export default ChangeConnection;
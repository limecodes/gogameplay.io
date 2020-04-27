import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { connectionChanged } from '../actions/visitor';

class ChangeConnection extends Component {

	componentDidMount() {
		if (navigator.connection) {
			// Commenting this for now.
			//navigator.connection.ontypechange = this.connectionDidChange.bind(this);
		}

		window.addEventListener('online', this.connectivityChange.bind(this));
		window.addEventListener('offline', this.connectivityChange.bind(this));
	}

	connectivityChange() {
		if (navigator.onLine) {
			console.log('connection regained');
		} else {
			console.log('connection lost');
		}
	}

	connectionDidChange(e) {
		setTimeout(function() { console.log('wait'); }, 1000);
		if (navigator.connection.type == 'cellular') {
			// I hate to do this, but looks like I have to
			this.props.connectionChanged(this.props.visitor.uid);
		}
	}



	render() {
		if ( (this.props.visitor.device == 'android') && (!this.props.visitor.connection) ) {
			return (
				<div className="alert alert-danger">Please switch to cellular connection</div>
			);
		} else if ( (this.props.visitor.device == 'ios') && (!this.props.visitor.connection) ) {
			return (
				<div>
					<div className="alert alert-danger">Please switch to cellular connection</div>
					<button className="btn btn-success" onClick={ this.connectionDidChange.bind(this) }>I've switched to cellular, Next ></button>
				</div>
			);
		} else {
			return <div></div>;
		}
	}

}

const mapStateToProps = state => ({
	visitor: state.visitor
})

export default connect(mapStateToProps, { connectionChanged })(ChangeConnection);
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { connectionChanged } from '../actions/visitor';

class ChangeConnection extends Component {

	componentDidMount() {
		if (navigator.connection) {
			navigator.connection.onchange = this.connectionDidChange.bind(this);
		}
	}

	connectionDidChange() {
		this.props.connectionChanged(this.props.visitor.uid);
	}

	render() {
		if ( (this.props.visitor.device == 'android') && (!this.props.visitor.connection) ) {
			return (
				<div className="alert alert-danger">Please switch to cellular connection</div>
			);
		} else if ( (this.props.visitor.device == 'ios') && (!this.props.connection) ) {
			return (
				<div>
					<div className="alert alert-danger">Please switch to cellular connection</div>
					<div className="btn btn-success">I've switched to cellular, Next ></div>
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
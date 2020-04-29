import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { setVisitorData } from '../actions/visitor';

import StackContainer from '../containers/StackContainer';
import StepContainer from '../containers/StepContainer';
import Instructions from './Instructions';
import Searching from './Searching';

class RootComponent extends Component {
	
	constructor(props) {
		super(props);
		// TODO: Only the app needs to know the device, it doesn't need to be recorded in the databasee
		// On android, I can get the connection right here via the navigator.connection
		// Here, I can initiate to record the user and get the uid
		// The uid can be used later
		// The objective is to make more efficient use of the API
		//this.props.setVisitorData(this.props.uid, this.props.device, (this.props.connection == "") ? false : true, (this.props.carrier !== 'unknown') ? this.props.carrier : '');
	}

	render() {

		if ( (this.props.validation.platform) && (this.props.validation.carrier) && (this.props.validation.search) ) {
			return (
				<div className="container">
					<Searching />
				</div>
			);
		} else {
			return (
		        <div className="container">
		            <StepContainer />
		        </div>
    		);
		}
	}
}

const mapStateToProps = state => ({
	visitor: state.visitor,
	validation: state.validation
});

export default connect(mapStateToProps, { setVisitorData })(RootComponent);
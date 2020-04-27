import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { setVisitorData } from '../actions/visitor';

import ChangeConnection from './ChangeConnection';

class RootComponent extends Component {
	
	constructor(props) {
		super(props);
		this.props.setVisitorData(this.props.uid, this.props.device, (this.props.connection == "") ? false : true);
	}

	render() {
		return (
	        <div className="container">
	            <div className="row justify-content-center">
	                <div className="col-md-8">
	                    <div className="card">
	                        <div className="card-header">Example Component</div>
	                        <ChangeConnection />
	                        <div className="card-body">{ this.props.visitor.device }</div>
	                    </div>
	                </div>
	            </div>
	        </div>
    	);
	}
}

const mapStateToProps = state => ({
	visitor: state.visitor
});

export default connect(mapStateToProps, { setVisitorData })(RootComponent);